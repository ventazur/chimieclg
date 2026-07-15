<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/* ----------------------------------------------------------------------------
 *
 * USAGER MODEL
 *
 * ---------------------------------------------------------------------------- */

class Usager_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

	/* ------------------------------------------------------------------------
	 *
     * Log de l'activite du site 
	 *
     *------------------------------------------------------------------------- */
    function log_activity()
    {
		if (in_array($_SERVER['REMOTE_ADDR'], $this->config->item('ne_pas_logger_ips')))
            return TRUE;

        $this->load->library('user_agent');

        $data = array(
            'url'        => str_replace($this->config->item('base_url'), '', current_url()),
            'adresse_ip' => $_SERVER['REMOTE_ADDR'],
            'user_agent' => $this->agent->agent_string(),
            'date'       => date_humanize(date('U'), TRUE),
            'epoch'      => date('U')
        ); 

        return $this->db->insert('activity_log', $data);
	}

    /* --------------------------------------------------------------------------------------------
     *
     * Verifier si l'adresse IP est autorisee
     *
     * -------------------------------------------------------------------------------------------- */
	function verifier_ip_autorise()
	{
		$ip_client = $this->input->ip_address();

		if (empty($ip_client)) 
		{
			return FALSE;
		}

		$ip_safe = $this->db->escape($ip_client);

		$this->db->select('1');
		$this->db->from('securite_connexion_ips');
		$this->db->where("adresse_ip_bin = INET6_ATON($ip_safe)", NULL, FALSE);

		$this->db->limit(1);

		return ($this->db->get()->num_rows() > 0);
	}

    /* --------------------------------------------------------------------------------------------
     *
     * Ajout d'une adresse IP autorisee
     *
     * -------------------------------------------------------------------------------------------- */
	public function ajouter_ip_autorise()
	{
		$ip_client = $this->input->ip_address();

		$data = array(
			'adresse_ip' => $ip_client,
			'date'       => date_humanize(date('U'), TRUE),
			'epoch'      => date('U')
		);

		$ip_safe = $this->db->escape($ip_client);

		$this->db->set('adresse_ip_bin', "INET6_ATON($ip_safe)", FALSE);

		return $this->db->insert('securite_connexion_ips', $data);
	}

    /* --------------------------------------------------------------------------------------------
     *
     * Verifier si le visiteur est un veritable robot Googlebot (sans challenge Turnstile)
     *
     * Le User-Agent seul est trivialement falsifiable, donc la requete n'est fiable qu'apres la
     * verification recommandee par Google elle-meme : le DNS inverse de l'IP doit resoudre vers un
     * hote googlebot.com/google.com, et la resolution directe de cet hote doit redonner la meme IP.
     * Le resultat est mis en cache par IP pour eviter un aller-retour DNS a chaque requete de crawl.
     *
     * -------------------------------------------------------------------------------------------- */
	function verify_googlebot()
	{
		$agent_string = $this->agent->agent_string();

		if (empty($agent_string) || stripos($agent_string, 'googlebot') === FALSE)
		{
			return FALSE;
		}

		$ip_client = $this->input->ip_address();

		if (empty($ip_client))
		{
			return FALSE;
		}

		$cache_key = 'verify_googlebot_' . $ip_client;

		if (($cached = $this->cache->get($cache_key)) !== FALSE)
		{
			return ($cached === 'valid');
		}

		$is_valid = FALSE;
		$hostname = gethostbyaddr($ip_client);

		if ($hostname !== FALSE && $hostname !== $ip_client)
		{
			$hostname_lc = strtolower($hostname);

			if (substr($hostname_lc, -14) === '.googlebot.com' || substr($hostname_lc, -11) === '.google.com')
			{
				if (gethostbyname($hostname) === $ip_client)
				{
					$is_valid = TRUE;
				}
			}
		}

		$this->cache->save($cache_key, $is_valid ? 'valid' : 'invalid', 86400);

		return $is_valid;
	}

    /* --------------------------------------------------------------------------------------------
     *
     * Verifier si le visiteur est un veritable robot OpenAI (sans challenge Turnstile)
     *
     * OpenAI n'offre pas de verification par DNS inverse pour ses robots, donc l'IP de la requete
     * est comparee aux plages officiellement publiees par OpenAI (recuperees quotidiennement par
     * Cli::fetch_ai_crawler_ips() et mises en cache sous 'ai_crawler_ips_openai'). Si le cache est
     * vide (ex. la recuperation quotidienne n'a pas encore tourne ou a echoue), la requete n'est
     * pas consideree fiable.
     *
     * -------------------------------------------------------------------------------------------- */
	function verify_openai_bot()
	{
		return $this->_verify_known_ai_crawler(
			array('gptbot', 'chatgpt-user', 'oai-searchbot'),
			'ai_crawler_ips_openai'
		);
	}

    /* --------------------------------------------------------------------------------------------
     *
     * Verifier si le visiteur est un veritable robot Anthropic/Claude (sans challenge Turnstile)
     *
     * Meme principe que verify_openai_bot() : le User-Agent est verifie en premier, puis l'IP de
     * la requete doit appartenir aux plages officiellement publiees par Anthropic, mises en cache
     * sous 'ai_crawler_ips_claude'.
     *
     * -------------------------------------------------------------------------------------------- */
	function verify_claude_bot()
	{
		return $this->_verify_known_ai_crawler(
			array('claudebot', 'claude-user', 'claude-searchbot'),
			'ai_crawler_ips_claude'
		);
	}

    /* --------------------------------------------------------------------------------------------
     *
     * Aide partagee : verifier un robot identifie par User-Agent contre une liste mise en cache
     * de plages CIDR pour ce fournisseur.
     *
     * -------------------------------------------------------------------------------------------- */
	private function _verify_known_ai_crawler($ua_needles, $cache_key)
	{
		$agent_string = $this->agent->agent_string();

		if (empty($agent_string))
		{
			return FALSE;
		}

		$matched = FALSE;

		foreach ($ua_needles as $needle)
		{
			if (stripos($agent_string, $needle) !== FALSE)
			{
				$matched = TRUE;
				break;
			}
		}

		if ( ! $matched)
		{
			return FALSE;
		}

		$ip_client = $this->input->ip_address();

		if (empty($ip_client))
		{
			return FALSE;
		}

		$prefixes = $this->cache->get($cache_key);

		if (empty($prefixes) || ! is_array($prefixes))
		{
			return FALSE;
		}

		foreach ($prefixes as $cidr)
		{
			if ($this->_ip_in_cidr($ip_client, $cidr))
			{
				return TRUE;
			}
		}

		return FALSE;
	}

    /* --------------------------------------------------------------------------------------------
     *
     * Verifier si une IP (v4 ou v6) appartient a une plage CIDR
     *
     * -------------------------------------------------------------------------------------------- */
	private function _ip_in_cidr($ip, $cidr)
	{
		if (strpos($cidr, '/') === FALSE)
		{
			return ($ip === $cidr);
		}

		list($subnet, $bits) = explode('/', $cidr, 2);

		$ip_bin     = @inet_pton($ip);
		$subnet_bin = @inet_pton($subnet);

		if ($ip_bin === FALSE || $subnet_bin === FALSE || strlen($ip_bin) !== strlen($subnet_bin))
		{
			return FALSE;
		}

		$bits           = (int) $bits;
		$bytes          = intdiv($bits, 8);
		$remainder_bits = $bits % 8;

		if ($bytes > 0 && substr($ip_bin, 0, $bytes) !== substr($subnet_bin, 0, $bytes))
		{
			return FALSE;
		}

		if ($remainder_bits === 0)
		{
			return TRUE;
		}

		$mask = chr((0xFF << (8 - $remainder_bits)) & 0xFF);

		return ((substr($ip_bin, $bytes, 1) & $mask) === (substr($subnet_bin, $bytes, 1) & $mask));
	}

    /* --------------------------------------------------------------------------------------------
     *
     * Recuperer les plages d'IP des robots IA (OpenAI, Anthropic/Claude)
     *
     * Lit la liste partagee et pre-fusionnee des plages d'IP de robots IA (produite par le cron
     * fetch_ai_crawler_ips.sh a partir des sources officielles OpenAI/Anthropic) et la met en cache
     * afin que verify_openai_bot() / verify_claude_bot() puissent confirmer un robot sans requete
     * externe a chaque visite.
     *
     * Le TTL du cache (48h) est volontairement plus long que l'intervalle du cron quotidien pour
     * qu'un seul echec de recuperation ne casse pas immediatement la verification le lendemain.
     *
     * Prevu pour etre execute quotidiennement via :
     * /usr/bin/php index.php cli fetch_ai_crawler_ips
     *
     * -------------------------------------------------------------------------------------------- */
	function fetch_ai_crawler_ips()
	{
		$url = $this->config->item('ai_crawler_ips_url');

		if (empty($url))
		{
			echo "AVERTISSEMENT : ai_crawler_ips_url n'est pas configure\n";
			return array();
		}

		$curl = curl_init();

		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 15);
		curl_setopt($curl, CURLOPT_USERAGENT, 'chimie-CrawlerIPFetcher/1.0');

		$body      = curl_exec($curl);
		$http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

		if ($body === FALSE || $http_code !== 200)
		{
			echo "AVERTISSEMENT : echec de recuperation de {$url} (HTTP {$http_code})\n";
			return array();
		}

		$data = json_decode($body, true);

		if ( ! is_array($data))
		{
			echo "AVERTISSEMENT : structure JSON inattendue depuis {$url}\n";
			return array();
		}

		$sources = array(
			'ai_crawler_ips_openai' => isset($data['openai']) ? $data['openai'] : array(),
			'ai_crawler_ips_claude' => isset($data['claude']) ? $data['claude'] : array(),
		);

		$summary = array();

		foreach ($sources as $cache_key => $prefixes)
		{
			$prefixes = array_values(array_unique((array) $prefixes));

			if ( ! empty($prefixes))
			{
				$this->cache->save($cache_key, $prefixes, 172800);
			}

			$summary[$cache_key] = count($prefixes);
		}

		return $summary;
	}

} // class
